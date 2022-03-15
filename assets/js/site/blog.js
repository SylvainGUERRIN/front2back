//import swal
const Swal = require('../../sweetalert2.all.min.js') //works

let heartBtnFavorite = document.getElementById('heartBtnFavorite')

let postID = document.getElementById('post-content').getAttribute('data-id')

heartBtnFavorite.addEventListener("click", function (e) {
    //traitement au click
    fetch('/ajax/user/addToFavorite', {
        method: "post",
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        //body: sendData
        body: JSON.stringify({
            id: postID
        })
    }).then(async result => {
        const data = await result.json();
        //console.log(data);
        //console.log(data[0]);
        if (data[0] === 'erreur lors de la demande') {
            Swal.fire(
                'Vous ne pouvez pas mettre un article en favori!',
                'Vous devez être connecté pour ajouter cet article dans vos favoris.',
                'warning'
            )
        } else {
            Swal.fire(
                data[0],
                'Vos veilles favorites sont a jour.',
                'success'
            )
        }


        // if (data[0][1] !== 'Le commentaire a déjà été validé.' && data[0][0] !== null) {
        //     let button = document.getElementById(data[0][0])
        //     button.classList.remove("valid-comment-button")
        //     button.classList.add("unvalid-comment-button")
        //     button.innerHTML = "Retirer le commentaire"
        //     window.location.reload()
        // }

    }).catch(error => {
        console.log(error);
    });
})

/*document.addEventListener("DOMContentLoaded", function () {
[].forEach.call(document.querySelectorAll('.valid-comment-button'), function (el) {
   el.addEventListener('click', function (e) {
       e.preventDefault()
       let val = el.getAttribute("id")
       //console.log(val)
       Swal.fire({
           title: 'Etes-vous sûrs de vouloir valider ce commentaire ?',
           text: "Une fois validé, il sera visible pour tous les visiteurs du site.",
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#3085d6',
           cancelButtonColor: '#d33',
           confirmButtonText: 'Oui, je veux valider ce commentaire !',
           cancelButtonText: 'annuler',
       }).then((result) => {
           if (result.value) {
               console.log(result.value)*/ //return true

/*const sendData = new FormData();
sendData.append("json", JSON.stringify({value: val}));*/

/*fetch('/admin/comments/ajax/valid-comment', {
    method: "post",
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    },
    //body: sendData
    body: JSON.stringify({
        id: val
    })
}).then(async result => {
    const data = await result.json();
    console.log(data[0]);
    //console.log(data[1]);
    Swal.fire(
        'Le commentaire a bien été validé!',
        'Il est désormais affiché sur le site.',
        'success'
    )

    if (data[0][1] !== 'Le commentaire a déjà été validé.' && data[0][0] !== null) {
        let button = document.getElementById(data[0][0])
        button.classList.remove("valid-comment-button")
        button.classList.add("unvalid-comment-button")
        button.innerHTML = "Retirer le commentaire"
        window.location.reload()
    }

}).catch(error => {
    console.log(error);
});
}
})
})
})
});*/
