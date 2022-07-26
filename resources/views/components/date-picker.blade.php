<div>
    <input
        x-data
        x-ref="input"
        x-init="new Pikaday({ 
            field: $refs.input, 
            numberOfMonths:2,
            theme: 'triangle-theme',
            minDate: new Date(),
            onClose: function() {
                $dispatch('input', $refs.input.value)
            },
            toString(date, format) {
                // you should do formatting based on the passed format,
                // but we will just return 'D/MMM/YYYY' for simplicity
                const day = date.getDate();
                const month = date.getMonth() + 1;
                const monthName = date.toLocaleString('en-US', {month: 'long'});
                const year = date.getFullYear();
                return `${day}-${monthName}-${year}`;
                return `${year}/${month}/${day}`;
            },
            parse(dateString, format) {
                // dateString is the result of `toString` method
                const parts = dateString.split('/');
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10) - 1;
                const year = parseInt(parts[2], 10);
                return new Date(year, month, day);
            }
        })"
        type="text"
        autocomplete="off" 
        {{ $attributes }}
    >
</div>
