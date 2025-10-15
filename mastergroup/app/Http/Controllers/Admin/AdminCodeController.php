<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminCodeController extends Controller
{
    public function index(Request $request)
    {
        $q      = trim((string)$request->get('q'));
        $status = $request->get('status'); // new|activated
        $type   = $request->get('type');   // welcome|promo|...
        $per    = (int)($request->get('per_page', 20));
        $per    = $per > 0 && $per <= 100 ? $per : 20;

        $query = Code::query()
            ->with(['activatedBy:id,full_name,email'])
            ->latest('id');

        if ($q !== '') {
            $query->where('code', 'like', "%{$q}%");
        }
        $query->status($status)->type($type);

        // Сортировка: сначала new, потом activated
        $query->orderByRaw("FIELD(status, 'new', 'activated')")->orderByDesc('id');

        $codes = $query->paginate($per)->appends($request->query());

        return view('admin.codes.index', [
            'title'  => 'Codes',
            'codes'  => $codes,
            'q'      => $q,
            'status' => $status,
            'type'   => $type,
            'per'    => $per,
            'types'  => config('codes.types'),
        ]);
    }

    public function create()
    {
        return view('admin.codes.add', [
            'title' => 'Add codes',
            'max'   => (int) config('codes.max_bulk', 20),
        ]);
    }

    public function store(Request $request)
    {
        $max = (int) config('codes.max_bulk', 20);
        $regex = config('codes.regex');

        // ввод: textarea с кодами по одному на строку
        $request->validate([
            'codes' => ['required','string'],
        ]);

        $lines = collect(preg_split('/\R/u', (string)$request->input('codes')))
            ->map(fn($s) => strtoupper(trim($s)))
            ->filter()                       // не пустые
            ->unique()
            ->values();

        if ($lines->count() === 0) {
            return back()->withInput()->withErrors(['codes' => 'Provide at least one code.']);
        }
        if ($lines->count() > $max) {
            return back()->withInput()->withErrors(['codes' => "Max {$max} codes at once."]);
        }

        // Валидация формата и уникальности
        $errors = [];
        foreach ($lines as $line) {
            if (!preg_match($regex, $line)) {
                $errors[] = "Invalid format: {$line}";
            }
        }
        if ($errors) {
            return back()->withInput()->withErrors(['codes' => implode("\n", $errors)]);
        }

        $duplicates = Code::query()->whereIn('code', $lines)->pluck('code')->all();
        if ($duplicates) {
            return back()->withInput()->withErrors([
                'codes' => 'Already exists: ' . implode(', ', $duplicates),
            ]);
        }

        // Сохраняем пачкой (модель сама проставит type/bonus_cps)
        DB::transaction(function () use ($lines) {
            foreach ($lines as $c) {
                Code::create([
                    'code'   => $c,
                    'status' => 'new',
                    // type/bonus_cps выставятся в booted() модели
                ]);
            }
        });

        return redirect()->route('admin.codes.index')->with('success', 'Codes added: ' . $lines->count());
    }
}
