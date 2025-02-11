export default function formatDate(isoString) {
    const date = new Date(isoString);
    const [day, month, year, hours, minutes, seconds] = [
        date.getUTCDate(),
        date.getUTCMonth() + 1,
        date.getUTCFullYear() % 100,
        date.getUTCHours(),
        date.getUTCMinutes(),
        date.getUTCSeconds()
    ].map(num => num.toString().padStart(2, '0'));

    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}

