// public/js/ui/datepicker.js
;(function (w) {
    const MONTHS = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ]
    const WEEKDAYS = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa']

    function format (d) {
        const mm = String(d.getMonth() + 1).padStart(2, '0')
        const dd = String(d.getDate()).padStart(2, '0')
        const yy = d.getFullYear()
        return `${mm}/${dd}/${yy}`
    }
    function parse (v) {
        const m = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/.exec((v || '').trim())
        if (!m) return null
        const mm = +m[1],
            dd = +m[2],
            yy = +m[3]
        const d = new Date(yy, mm - 1, dd)
        return d.getFullYear() === yy &&
            d.getMonth() === mm - 1 &&
            d.getDate() === dd
            ? d
            : null
    }
    function addMonths (d, n) {
        const x = new Date(d.getFullYear(), d.getMonth() + n, 1)
        const day = Math.min(
            d.getDate(),
            new Date(x.getFullYear(), x.getMonth() + 1, 0).getDate()
        )
        return new Date(x.getFullYear(), x.getMonth(), day)
    }

    function initInput (input) {
        // обёртка для позиционирования
        const wrap = document.createElement('div')
        wrap.className = 'dp-anchor'
        input.parentNode.insertBefore(wrap, input)
        wrap.appendChild(input)

        let selected = parse(input.value)
        let view = selected || new Date()
        let dp = null

        input.addEventListener('focus', open)
        input.addEventListener('click', open)
        input.addEventListener('input', onMask)
        input.addEventListener('blur', () => {
            const d = parse(input.value)
            if (d) input.value = format(d)
        })

        function onMask () {
            let v = input.value.replace(/[^\d]/g, '').slice(0, 8)
            const parts = []
            if (v.length >= 2) {
                parts.push(v.slice(0, 2))
                v = v.slice(2)
            } else {
                parts.push(v)
                v = ''
            }
            if (v.length >= 2) {
                parts.push(v.slice(0, 2))
                v = v.slice(2)
            } else if (v) {
                parts.push(v)
                v = ''
            }
            if (v.length) parts.push(v)
            input.value = parts.join('/')
            const d = parse(input.value)
            if (d) selected = d
        }
        let onDocDown = null
        function open () {
            if (dp) return // если уже открыт — не пересоздаём
            closeAll()
            dp = render()
            wrap.appendChild(dp)

            // позиционирование под инпутом
            dp.style.position = 'absolute'
            dp.style.left = '0'
            dp.style.top = 'calc(100% + 6px)'

            // единый "клик-вне"
            onDocDown = e => {
                const insideDp = e.target.closest('.dp') === dp
                const onSameInput = e.target === input
                if (!insideDp && !onSameInput) {
                    close()
                }
            }
            document.addEventListener('pointerdown', onDocDown, true)
        }

        function close () {
            if (dp && dp.parentNode) dp.parentNode.removeChild(dp)
            dp = null
            if (onDocDown) {
                document.removeEventListener('pointerdown', onDocDown, true)
                onDocDown = null
            }
        }

        function render () {
            const el = document.createElement('div')
            el.className = 'dp'
            el.innerHTML = `
        <div class="dp-header">
          <button type="button" class="dp-nav-btn" data-nav="-1" aria-label="Previous month">‹</button>
          <div class="dp-month"></div>
          <button type="button" class="dp-nav-btn" data-nav="1" aria-label="Next month">›</button>
        </div>
        <div class="dp-grid dp-week">
          ${WEEKDAYS.map(w => `<div class="dp-weekday">${w}</div>`).join('')}
        </div>
        <div class="dp-grid dp-days"></div>
        <div class="dp-footer">
          <button type="button" class="dp-quick" data-today>Today</button>
          <button type="button" class="dp-quick" data-clear>Clear</button>
        </div>
      `

            el.querySelectorAll('[data-nav]').forEach(btn => {
                btn.addEventListener('click', () => {
                    view = addMonths(view, Number(btn.dataset.nav))
                    paint()
                })
            })
            el.querySelector('[data-today]').addEventListener('click', () => {
                const now = new Date()
                selected = new Date(
                    now.getFullYear(),
                    now.getMonth(),
                    now.getDate()
                )
                view = selected
                input.value = format(selected)
                close()
                input.dispatchEvent(new Event('change', { bubbles: true }))
            })
            el.querySelector('[data-clear]').addEventListener('click', () => {
                selected = null
                input.value = ''
                close()
                input.dispatchEvent(new Event('change', { bubbles: true }))
            })



            paint()
            return el

            function paint () {
                const monthEl = el.querySelector('.dp-month')
                const daysEl = el.querySelector('.dp-days')
                monthEl.textContent = `${
                    MONTHS[view.getMonth()]
                } ${view.getFullYear()}`
                daysEl.innerHTML = ''

                const first = new Date(view.getFullYear(), view.getMonth(), 1)
                const start = new Date(first)
                start.setDate(first.getDate() - first.getDay())

                for (let i = 0; i < 42; i++) {
                    const d = new Date(start)
                    d.setDate(start.getDate() + i)
                    const cell = document.createElement('div')
                    cell.className = 'dp-day'
                    cell.textContent = d.getDate()
                    if (d.getMonth() !== view.getMonth())
                        cell.classList.add('is-out')

                    const today = new Date()
                    const d0 = new Date(
                        today.getFullYear(),
                        today.getMonth(),
                        today.getDate()
                    )
                    if (d.getTime() === d0.getTime())
                        cell.classList.add('is-today')

                    if (selected && d.getTime() === selected.getTime())
                        cell.classList.add('is-selected')

                    cell.addEventListener('click', () => {
                        selected = new Date(
                            d.getFullYear(),
                            d.getMonth(),
                            d.getDate()
                        )
                        input.value = format(selected)
                        close()
                        input.dispatchEvent(
                            new Event('change', { bubbles: true })
                        )
                    })

                    daysEl.appendChild(cell)
                }
            }
        }
    }

    function closeAll () {
        document.querySelectorAll('.dp').forEach(el => el.remove())
    }

    w.initDatepickers = function (root = document) {
        root.querySelectorAll('[data-datepicker]').forEach(initInput)
    }
})(window)
