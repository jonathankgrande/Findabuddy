function toggleDay(dayId) {
    const toggle = document.getElementById(`toggle-${dayId}`);
    const dropdown = document.getElementById(`time-${dayId}`);
    if (toggle.value === 'yes') {
        dropdown.disabled = false;
        dropdown.classList.remove("bg-gray-200", "text-gray-400");
    } else {
        dropdown.disabled = true;
        dropdown.value = '';
        dropdown.classList.add("bg-gray-200", "text-gray-400");
    }
}
