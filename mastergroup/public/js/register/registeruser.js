/* =========================================================
   public/js/register/registeruser.js
   Регистрация пользователя — логика формы (2 шага)
   Чистый JS. Без зависимостей.
   Структура:
   1) Хелперы и конфиг
   2) Инициализация UI
   3) Шаг 1: валидация и переход
   4) Шаг 2: валидация и отправка
   ========================================================= */

document.addEventListener("DOMContentLoaded", () => {
    /* =========================
       1) ХЕЛПЕРЫ И КОНФИГ
       ========================= */
    const emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // простой валидатор email
    const phoneRe = /^\+[0-9]{8,15}$/; // международный формат E.164

    const qs = (sel, root = document) => root.querySelector(sel);
    const qsa = (sel, root = document) =>
        Array.from(root.querySelectorAll(sel));

    const setBtnDisabled = (btn, disabled) => {
        if (!btn) return;
        btn.classList.toggle("is-disabled", !!disabled);
        btn.toggleAttribute("disabled", !!disabled);
        btn.setAttribute("aria-disabled", String(!!disabled));
    };

    /* =========================
       2) ИНИЦИАЛИЗАЦИЯ UI
       ========================= */
    // upload + кастомные селекты (гендер/страна)
    window.initFileUploads?.();
    window.initCustomSelects?.(document); // первичная инициализация для всех [data-cselect] (включая скрытые)

    const form = qs("#regForm");
    if (!form) return;
    const clientType =
        form.querySelector('input[name="client_type"]')?.value || "individual";
    const isCompany = clientType === "company";

    const step1 = qs('[data-step="1"]', form);
    const step2 = qs('[data-step="2"]', form);

    // Кнопки управления
    const nextBtn = qs(".form_next", step1);
    const back2 = qs(".form_back", step2); // может отсутствовать
    const submitBtn = qs(".form_submit", step2);

    /* =========================
       3) ШАГ 1
       ========================= */
    // Поля шага 1
    const uploadBlocks = qsa(".file-upload", step1); // 2 блока
    const nameInput = qs('input[placeholder="Name and Surname *"]', step1);
    const birthInput = qs('input[name="birth_date"]', step1);
    const genderSelect = qs('select[name="gender"]', step1);
    const instaInput = qs('input[placeholder="Instagram account"]', step1); // опционально

    // ВАЖНО: телефон теперь может быть на шаге 1 (для company)
    const phone = qs('input[name="phone"]', form); // ИЩЕМ ПО ВСЕЙ ФОРМЕ, а не только в step2

    const blockHasFile = (b) => {
        if (!b) return false;
        if (b.classList?.contains("has-file")) return true;
        const input = qs(".file-input", b);
        if (input?.files?.length) return true;
        const preview = qs(".file-preview", b);
        return preview && preview.hidden === false;
    };

    const nameBlk = nameInput?.closest(".register_user-input");
    const birthBlk = birthInput?.closest(".register_user-birth");
    const genderBlk = genderSelect?.closest(".register_user-gender");

    function setFieldError(block, message) {
        if (!block) return;
        block.classList.add("has-error");

        let err = block.querySelector(".field-error");
        if (!err) {
            err = document.createElement("div");
            err.className = "field-error";
            block.appendChild(err);
        }
        err.textContent = message;
    }

    function clearFieldError(block) {
        if (!block) return;
        block.classList.remove("has-error");
        const err = block.querySelector(".field-error");
        if (err) err.remove();
    }

    function validDateMMDDYYYY(value) {
        const m = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec(value);
        if (!m) return false;
        const mm = +m[1],
            dd = +m[2],
            yy = +m[3];
        const d = new Date(yy, mm - 1, dd);
        return (
            d.getFullYear() === yy &&
            d.getMonth() === mm - 1 &&
            d.getDate() === dd
        );
    }

    const isStep1Valid = () => {
        let valid = true;

        // FILES (для company 2 файла, для individual 2 других)
        uploadBlocks.forEach((block) => {
            if (!blockHasFile(block)) {
                valid = false;
                block.classList.add("has-error");
            } else {
                block.classList.remove("has-error");
            }
        });

        // NAME
        if (!nameInput.value.trim() || nameInput.value.trim().length < 3) {
            setFieldError(nameBlk, "Please enter full name.");
            valid = false;
        } else {
            clearFieldError(nameBlk);
        }

        // BIRTH DATE
        if (!validDateMMDDYYYY(birthInput.value.trim())) {
            setFieldError(birthBlk, "Invalid date format.");
            valid = false;
        } else {
            clearFieldError(birthBlk);
        }

        // GENDER
        if (!genderSelect.value) {
            setFieldError(genderBlk, "Select gender.");
            valid = false;
        } else {
            clearFieldError(genderBlk);
        }

        // PHONE (company step1)
        if (isCompany) {
            if (!phone.value || !phoneRe.test(phone.value.trim())) {
                valid = false;
                phone.classList.add("input-error");
            } else {
                phone.classList.remove("input-error");
            }

            const insta = qs('input[name="instagram"]', step1);
            if (!insta || !insta.value.trim()) {
                valid = false;
                insta?.classList.add("input-error");
            } else {
                insta?.classList.remove("input-error");
            }
        }

        return valid;
    };

    const updateNextState = () => setBtnDisabled(nextBtn, !isStep1Valid());

    // Слушатели для пересчёта состояния "NEXT"
    uploadBlocks.forEach((b) => {
        const inp = qs(".file-input", b);
        inp?.addEventListener("change", updateNextState);
        b.addEventListener("fileupload:change", updateNextState);
    });
    [nameInput, birthInput, genderSelect, instaInput].forEach((el) => {
        el?.addEventListener("input", updateNextState);
        el?.addEventListener("change", updateNextState);
    });

    updateNextState();

    // Маска телефона (работает где бы поле ни стояло — step1/step2)
    phone?.addEventListener("input", () => {
        let v = phone.value.replace(/[^\d+]/g, "");
        v = v.replace(/\+/g, "");
        v = "+" + v.replace(/\D/g, "").slice(0, 15);
        phone.value = v;
    });

    // Переход на шаг 2
    nextBtn?.addEventListener("click", () => {
        if (nextBtn.classList.contains("is-disabled")) return;

        step1.classList.add("is-hidden");
        step2.classList.remove("is-hidden");
        step2.scrollIntoView({ behavior: "smooth", block: "start" });

        // Доинициализировать кастомные селекты внутри шага 2 (если еще не были привязаны)
        window.initCustomSelects?.(step2);

        updateSubmitState();
    });

    // Назад со 2-го шага (если есть кнопка)
    back2?.addEventListener("click", () => {
        step2.classList.add("is-hidden");
        step1.classList.remove("is-hidden");
        step1.scrollIntoView({ behavior: "smooth", block: "start" });
        updateNextState();
    });

    /* =========================
       4) ШАГ 2
       ========================= */
    // Поля шага 2
    const email2 = qs('input[name="email"]', step2);
    const country = qs('select[name="country"]', step2);
    const pass1 = qs("#regPassword", step2);
    const pass2 = qs("#regPassword2", step2);
    const passwordHint = document.getElementById("passwordHint");
    if (!pass1 || !pass2) {
        console.warn("Password inputs not found");
    }

    function validatePasswordsLive() {
        if (!pass1 || !pass2) return;

        const val1 = pass1.value;
        const val2 = pass2.value;

        if (passwordHint) {
            if (val1.length === 0) {
                passwordHint.style.color = "#d41414";
            } else if (val1.length < 8) {
                passwordHint.textContent =
                    "Password must be at least 8 characters";
                passwordHint.style.color = "#d41414";
            } else {
                passwordHint.textContent = "Password length OK";
                passwordHint.style.color = "#16a34a";
            }
        }

        if (val2.length > 0) {
            if (val1 !== val2) {
                pass2.classList.add("input-error");
            } else {
                pass2.classList.remove("input-error");
            }
        }
    }

    pass1.addEventListener("input", validatePasswordsLive);
    pass2.addEventListener("input", validatePasswordsLive);

    const agreeChk = qs("#agree", step2);

    // Блоки для подсветки (ищем ближайший контейнер, чтобы не зависеть от ID)
    const email2Blk =
        email2?.closest(".register_user-input") ||
        qs("#email2Blk", step2) ||
        step2;
    const phoneBlk =
        phone?.closest(".register_user-input") ||
        qs("#phoneBlk", step2) ||
        form;
    const countryBlk =
        qs("#countryBlk", step2) ||
        country?.closest(".register_user-input") ||
        step2;
    const pass1Blk =
        qs("#pass1Blk", step2) ||
        pass1?.closest(".register_user-input") ||
        step2;
    const pass2Blk =
        qs("#pass2Blk", step2) ||
        pass2?.closest(".register_user-input") ||
        step2;

    // Глаза для паролей
    qsa(".form-block--with-eye .input-eye", step2).forEach((btn) => {
        btn.addEventListener("click", () => {
            const input = btn.previousElementSibling;
            if (!input) return;
            const isShown = input.type === "text";
            input.type = isShown ? "password" : "text";
            btn.classList.toggle("is-on", !isShown);
            btn.setAttribute("aria-pressed", String(!isShown));
        });
    });

    // Валидация шага 2
    const isStep2Valid = () => {
        let valid = true;

        const emailVal = email2?.value.trim() || "";
        const pwd1Val = pass1?.value || "";
        const pwd2Val = pass2?.value || "";

        // ADDRESS (company использует name="work")
        const addressInput =
            qs('input[name="work"]', step2) ||
            qs('input[name="workplace"]', step2);

        if (!addressInput || !addressInput.value.trim()) {
            addressInput?.classList.add("input-error");
            valid = false;
        } else {
            addressInput.classList.remove("input-error");
        }

        // EMAIL
        if (!emailRe.test(emailVal)) {
            email2?.classList.add("input-error");
            valid = false;
        } else {
            email2?.classList.remove("input-error");
        }

        // COUNTRY
        if (!country?.value) {
            countryBlk?.classList.add("has-error");
            valid = false;
        } else {
            countryBlk?.classList.remove("has-error");
        }

        // PASSWORD LENGTH
        if (pwd1Val.length < 8) {
            pass1?.classList.add("input-error");
            valid = false;
        } else {
            pass1?.classList.remove("input-error");
        }

        // PASSWORD MATCH
        if (!pwd2Val || pwd1Val !== pwd2Val) {
            pass2?.classList.add("input-error");
            valid = false;
        } else {
            pass2?.classList.remove("input-error");
        }

        // AGREE
        if (!agreeChk?.checked) {
            valid = false;
        }
        console.log("STEP2 VALID:", {
            email: emailVal,
            address: qs('input[name="work"]', step2)?.value,
            country: country?.value,
            pwd1: pwd1Val,
            pwd2: pwd2Val,
            agree: agreeChk?.checked,
            valid,
        });
        return valid;
    };

    const updateSubmitState = () => setBtnDisabled(submitBtn, !isStep2Valid());

    // Слушатели для пересчёта
    const workplaceInput = qs('input[name="work"]', step2);

    [email2, country, pass1, pass2, workplaceInput].forEach((el) => {
        el?.addEventListener("input", () => {
            isStep2Valid(); // чтобы ошибки появлялись сразу
            updateSubmitState();
        });
        el?.addEventListener("change", () => {
            isStep2Valid();
            updateSubmitState();
        });
    });
    agreeChk?.addEventListener("change", updateSubmitState);
    updateSubmitState();
    // Страховка на submit
    form.addEventListener("submit", (e) => {
        if (!isStep2Valid()) {
            e.preventDefault();
            updateSubmitState();
        }
    });
});
