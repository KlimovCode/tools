function getBadName(name, age) {
    return `${name} ${age}`
}
console.log(getBadName('Olya', 25));

function getGoodName(obj) {
    return `${obj.name} ${obj.age}`
}
console.log(getGoodName({
    name: 'Olya', 
    age: 25
}));