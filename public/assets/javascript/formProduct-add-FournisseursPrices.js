let btn = $('#ajoutFournisseursPrices'),
    fournisseurPricesToCopy = $('#fournisseursPrices .form-group').clone();


btn.click(function () {
    $('#fournisseursPrices').append('<hr>');
    $('#fournisseursPrices').append(fournisseurPricesToCopy.clone());
});