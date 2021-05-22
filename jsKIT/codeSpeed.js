console.time('point 1');

let a = 1, b = 2

for(let i = 0; i < 1000000; i++) {
    [ a, b ] = [ b, a ]
}

console.timeEnd('point 1');

console.time('point 2');

let x = 1, y = 2

for(let i = 0; i < 1000000; i++) {
    let t = x
    x = y
    y = t 
}

console.timeEnd('point 2');
