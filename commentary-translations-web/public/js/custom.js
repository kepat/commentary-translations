// Uppercase all the first letter of the word of the string
function ucwords(value) {
    return (value + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
        return $1.toUpperCase();
    });
}