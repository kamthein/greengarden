window.onload = () => {
    const FiltersForm = document.querySelector("#filtres");

    document.querySelectorAll("#filtres input").forEach(input => {
        input.addEventListener("change", () => {
            //Ici, on intercepter les clics sur les checkbox Méthodes
            // On récupère les données du formulaires
            const Form = new FormData(FiltersForm);

            //On fabrique la "queryString"
            const Params = new URLSearchParams();

            Form.forEach((value, key) => {
                Params.append(key, value);
            });

            // On récupère l'URL active
            const Url = new URL(window.location.href);

            //On lance la requête AJAX
            fetch(Url.pathname + "?" + Params.toString() + "&ajax=1",{
                headers: {
                    "X-Requested-With": "XMLHttpRequest"
                }
                }).then(response =>
                response.json()
            ).then(data => {
                // On va chercher la zone de contenu
                const content = document.querySelector("#content");
                // On remplace la zone de contenu
                content.innerHTML = data.content;
            }).catch(e=> alert(e));
        });
    });
}
