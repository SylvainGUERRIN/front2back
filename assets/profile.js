
const response = fetch('/ajax/check/contributing')
    .then(async result => {
        const data = await result.json();
        console.log(data);
    }).catch(error => {
        console.log(error);
    });

// const response = fetch("https://localhost:8000/ajax/check/contributing", {
//     method: "POST",
//     headers: new Headers({
//         "Content-Type": "application/json"
//     }),
//     body: JSON.stringify(order), //stringifying the objet
// })
