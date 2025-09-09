const otpInputs = document.querySelectorAll('input[name="1"], input[name="2"], input[name="3"], input[name="4"]');

otpInputs.forEach((input, index) => {
    input.addEventListener('input', (e) => {
        if (e.target.value.length === 1 && index < 3) {
            otpInputs[index + 1].focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && index > 0) {
            otpInputs[index - 1].focus();
        }
    });
});

function previewFile(inputId) {
    const input = document.getElementById(inputId);
    input.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const preview = document.createElement('p');
            preview.className = 'text-sm text-green-500 mt-2';
            preview.innerText = `Selected: ${file.name}`;
            if (this.nextElementSibling) this.nextElementSibling.remove();
            this.parentNode.appendChild(preview);
        }
    });
}

['lic_img', 'rc_img', 'idn', 'prf'].forEach(id => previewFile(id));

window.addEventListener('DOMContentLoaded', () => {
    form = document.querySelector('.register');
    form.addEventListener('submit', (e) => {
        const requiredFields = ['first_name', 'last_name', 'email', 'phone', 'p_no', 'stt', 'cnt', 'rc_no', 'veh_no'];
        let isValid = true;

        requiredFields.forEach(id => {
            const field = document.getElementById(id);
            if (field && field.value.trim() === '') {
                isValid = false;
                field.classList.add('border-red-500');
            } else if (field) {
                field.classList.remove('border-red-500');
            }
        });
        const licenseSection = document.getElementById('licenseSection');
        if (licenseSection.style.display !== 'none') {
            const licenseField = document.getElementById('l_no');
            if (licenseField && licenseField.value.trim() === '') {
                isValid = false;
                licenseField.classList.add('border-red-500');
            } else if (licenseField) {
                licenseField.classList.remove('border-red-500');
            }
        }

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
        else
        {
            form.style.display = "none";
        }
    });
});
function setPage() {
    if (document.getElementById("v_yes").checked) {
        document.getElementById("licenseSection").style.display = "block";
    }
    else {
        document.getElementById("licenseSection").style.display = "none";
    }
}