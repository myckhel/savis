export default (formArray) => {
  let data = {};
  formArray.map((line) => {
    let name = line.name
    const value = line.value
    if (name.slice(-2) === '[]') {
      name = name.substr(0,name.length -2)
        if (!data[name]) {
          data[name] = []
        }
        data[name].push(value)
    } else {
      data[name] = value
    }
  })
  return data
}
