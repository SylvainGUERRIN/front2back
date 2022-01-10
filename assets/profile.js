const response = fetch('/ajax/check/contributing', {
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json'
    }
})
    .then(async result => {
        const data = await result.json();
        console.log(data);
        if (data[0] === true) {
            const checkbox = document.getElementById('edit_profile_requests');
            checkbox.checked = true;
        }
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
