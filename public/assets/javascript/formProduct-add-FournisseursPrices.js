let btn = $('#ajoutFournisseursPrices'),
    fournisseurPricesToCopy = $('#fournisseursPrices .form-group').clone();


btn.click(function () {
    console.log(fournisseurPricesToCopy);
    $('#fournisseursPrices').append('<hr>');
    $('#fournisseursPrices').append(fournisseurPricesToCopy.clone());
});