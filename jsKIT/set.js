const data = {
    name: 'Olya',
    age: 25,
    intresting: [ 'study', 'health', [1,2,3]],
    nums: [1,3,4,5,4,3,4,2,1]
}

console.log(new Set(data.nums)); // {1,3,4,5,2}
console.log([...new Set(data.nums)]); // [1,3,4,5,2]