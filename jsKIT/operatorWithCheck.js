const test = {
    arr: [1,23,4]
}
console.log(test.arr.join(' ')); // 1, 23, 4
console.log(test.arrErrorCheck?.join(' ')); // [WARNING] undefined
console.log(test.arrError.join(' ')); // [ERROR] Cannot read property of undefined