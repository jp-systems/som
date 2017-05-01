export default function (format) {
  let now = new Date()
  let date = new Date(format)
  if (now.getDate() === date.getDate() && now.getMonth() === date.getMonth() && date.getFullYear() === now.getFullYear()) {
    // Same day
    let hours = date.getHours() < 10 ? '0' + date.getHours() : date.getHours()
    let mins = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes()
    return hours + ':' + mins
  } else if (now.getFullYear() === date.getFullYear()) {
    return date.getDate() + ' ' + ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][date.getMonth() - 1]
  } else {
    // In the past
    return date.getDate() + '/' + date.getMonth() + '/' + String(date.getFullYear()).slice(2, 4)
  }
}
