const data = {
    name: 'Olya',
    age: 25,
    intresting: [ 'study', 'health', [1,2,3]]
}

const temp = [...data.intresting, 1,2,3]

console.log(temp);