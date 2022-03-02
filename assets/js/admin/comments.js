//change all code below this line with vanilla js
/*$(document).ready(function () {
    $(document).on("click", ".valid-comment-button", function (e) {
        e.preventDefault;
        let val = $(this).attr("id");
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
                $.ajax({
                    url: '/sloune-admin/administration/commentaires/ajax/valid-comment',
                    type: 'POST',
                    data: {value: val},
                    dataType: 'json',
                    async: true,

                    success: function (data, response) {
                        Swal.fire(
                            'Le commentaire a bien été validé!',
                            'Il est désormais affiché sur le site.',
                            'success'
                        )

                        if (data !== 'Le commentaire a déjà été validé') {
                            let button = document.getElementById(data)
                            button.classList.remove("valid-comment-button")
                            button.classList.add("unvalid-comment-button")
                            button.innerHTML = "Retirer le commentaire"
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert('La requête ajax a échoué.');
                    }
                });
            }
        })
    });

    $(document).on("click", ".unvalid-comment-button", function (e) {
        e.preventDefault;
        let val = $(this).attr("id");
        Swal.fire({
            title: 'Etes-vous sûrs de vouloir retirer ce commentaire ?',
            text: "Une fois retiré, il sera plus visible pour les visiteurs du site.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, je veux retirer ce commentaire !',
            cancelButtonText: 'annuler',
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/sloune-admin/administration/commentaires/ajax/unvalid-comment',
                    type: 'POST',
                    data: {value: val},
                    dataType: 'json',
                    async: true,

                    success: function (data, response) {
                        Swal.fire(
                            'Le commentaire a bien été retiré!',
                            'Il n\'est désormais plus affiché sur le site.',
                            'success'
                        )
                        if (data !== 'Le commentaire a déjà été retiré') {
                            let button = document.getElementById(data)
                            button.classList.remove("unvalid-comment-button")
                            button.classList.add("valid-comment-button")
                            button.innerHTML = "Valider le commentaire"
                        }
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        // alert('La requête ajax a échoué.');
                    }
                });
            }
        })
    });
});*/

//vanilla js
//import swal
const Swal = require('../../sweetalert2.all.min.js') //works
//const Swal = import('../../../node_modules/sweetalert/dist/sweetalert2.min.js') //not working because not installed
//const Swal = import('./sweetalert2.all.min.js') //not working
// const Swal = require('../../../node_modules/sweetalert/dist/sweetalert.min.js')
document.addEventListener("DOMContentLoaded", function () {
    [].forEach.call(document.querySelectorAll('.valid-comment-button'), function (el) {
        el.addEventListener('click', function (e) {
            e.preventDefault()
            let val = el.getAttribute("id")
            console.log(val)
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
                    console.log(result.value) //return true

                    /*const sendData = new FormData();
                    sendData.append("json", JSON.stringify({value: val}));*/

                    fetch('/admin/comments/ajax/valid-comment', {
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
});

document.addEventListener("DOMContentLoaded", function () {
        [].forEach.call(document.querySelectorAll('.unvalid-comment-button'), function (el) {
            el.addEventListener('click', function (e) {
                e.preventDefault()
                let val = el.getAttribute("id")
                console.log(val)
                Swal.fire({
                    title: 'Etes-vous sûrs de vouloir supprimer ce commentaire ?',
                    text: "Une fois supprimé, il ne sera pas visible pour tous les visiteurs du site.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, je veux supprimer ce commentaire !',
                    cancelButtonText: 'annuler',
                }).then((result) => {
                    if (result.value) {
                        console.log(result.value) //return true

                        fetch('/admin/comments/ajax/unvalid-comment', {
                            method: "post",
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                id: val
                            })
                        }).then(async result => {
                            const data = await result.json();
                            console.log(data[0]);
                            //console.log(data[1]);
                            Swal.fire(
                                'Le commentaire a bien été supprimé!',
                                'Il n\'est désormais plus visible sur le site.',
                                'success'
                            )

                        if (data[0][1] !== 'Le commentaire a déjà été retiré.' && data[0][0] !== null) {
                            let button = document.getElementById(data[0][0])
                            button.classList.remove("unvalid-comment-button")
                            button.classList.add("valid-comment-button")
                            button.innerHTML = "Valider le commentaire"
                            window.location.reload()
                        }

                        }).catch(error => {
                            console.log(error);
                        });
                    }
                })
            })
        })
});
