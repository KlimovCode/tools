function* numGenerator() {
    let num = 1
    if(true) {
        yield num++
    }
}
const myNumGenerator = numGenerator()
console.log(myNumGenerator.next()); // { value: 1, done: false}
console.log(myNumGenerator.next()); // { value: undefined, done: true}

function* numGenerator2() {
    let num = 1
    while(true) {
        yield num++
    }
}
const myNumGenerator2 = numGenerator2()
console.log(myNumGenerator2.next()); // { value: 1, done: false}
console.log(myNumGenerator2.next()); // { value: 2, done: false}