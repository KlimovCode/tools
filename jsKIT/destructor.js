const data = {
    name: 'Olya',
    age: 25,
    intresting: [ 'study', 'health']
}
const { name, age } = data
console.log(name, age);

const { 0: main, 1: submain} = data.intresting
console.log(main, submain);