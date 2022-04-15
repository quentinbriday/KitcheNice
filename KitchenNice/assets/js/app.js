/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.css');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.

require('select2');

$('.select_multiple').select2();

var $collectionQuantiteHolder;

// setup an "add a tag" link
var $addQuantiteButton = $('<button type="button" class=" btn btn-primary add_quantite_link">+</button>');
var $newLinkQuantiteDiv = $('<div></div>').append($addQuantiteButton);


jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionQuantiteHolder = $('#quantite');

    // add the "add a tag" anchor and li to the tags ul
    $collectionQuantiteHolder.append($newLinkQuantiteDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionQuantiteHolder.data('index', $collectionQuantiteHolder.find(':input').length);

    $addQuantiteButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addQuantiteForm($collectionQuantiteHolder, $newLinkQuantiteDiv);
    });
});

function addQuantiteForm($collectionQuantiteHolder, $newLinkDiv) {
    // Get the data-prototype explained earlier
    var prototype = $collectionQuantiteHolder.data('prototype');

    // get the new index
    var index = $collectionQuantiteHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionQuantiteHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormDiv = $('<div></div>').append(newForm);
    $newLinkDiv.before($newFormDiv);

    addQuantiteFormDeleteLink($newFormDiv);
}

function addQuantiteFormDeleteLink($quantiteFormDiv) {
    var $removeFormButton = $('<div class="col-md-1"><button type="button" class="btn btn-danger">-</button></div>');
    $quantiteFormDiv.find('.row').append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $quantiteFormDiv.remove();
    });
}

var $collectionEtapeHolder;

// setup an "add a tag" link
var $addEtapeButton = $('<button type="button" class=" btn btn-primary add_etape_link">+</button>');
var $newLinkEtapeDiv = $('<div></div>').append($addEtapeButton);


jQuery(document).ready(function() {
    // Get the ul that holds the collection of tags
    $collectionEtapeHolder = $('#etape');

    // add the "add a tag" anchor and li to the tags ul
    $collectionEtapeHolder.append($newLinkEtapeDiv);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionEtapeHolder.data('index', $collectionEtapeHolder.find(':input').length);

    $addEtapeButton.on('click', function(e) {
        // add a new tag form (see next code block)
        addQuantiteForm($collectionEtapeHolder, $newLinkEtapeDiv);
    });
});

function addEtapeForm($collectionEtapeHolder, $newLinkEtapeDiv) {
    // Get the data-prototype explained earlier
    var prototype = $collectionEtapeHolder.data('prototype');

    // get the new index
    var index = $collectionEtapeHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionEtapeHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormDiv = $('<div></div>').append(newForm);
    $newLinkEtapeDiv.before($newFormDiv);

    addEtapeFormDeleteLink($newFormDiv);
}

function addEtapeFormDeleteLink($etapeFormDiv) {
    var $removeFormButton = $('<div class="col-md-1"><button type="button" class="btn btn-danger">-</button></div>');
    $etapeFormDiv.find('.row').append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $etapeFormDiv.remove();
    });
}




var $quantiteAModifier = $(".nb_personne");
const $quantiteOriginale = parseInt($(".nb_personne_original").val());

var $quantites = [];
$("#quantite_base").find(".quantite").each(function () {
    $quantites.push(this);
});

var $tabQuantiteBase = [];
$("input.quantite_originale").each(function () {
    //Trouver le moyen de récupérer la valeur du .quantite_originale
    $tabQuantiteBase.push(this.value);
});

$(".increment_minus").click(function(){
    if ($quantiteAModifier.val() > 1){
        $quantiteAModifier.val($quantiteAModifier.val()-1);
        $quantiteAModifier.change();
    }
});
$(".increment_plus").click(function(){
    var $newNbPersonne = parseInt($quantiteAModifier.val());
    $quantiteAModifier.val($newNbPersonne+1);
    $quantiteAModifier.change();
});


$quantiteAModifier.change(function () {

    var i;
    if(parseInt($quantiteAModifier.val()) > 0)
    {
        var $divise = 1;
        var $mult = false;

        if(parseInt($quantiteAModifier.val()) > $quantiteOriginale)
        {
            $divise = parseInt($quantiteAModifier.val()) / $quantiteOriginale;
            $mult = true;
        }
        else
        {
            $divise = $quantiteOriginale / parseInt($quantiteAModifier.val());
        }

        if($mult)
        {
            for (i = 0; i < $quantites.length; i++) {
                $quantites[i].innerText = $tabQuantiteBase[i] * $divise;
            }
        }
        else
        {
            for (i = 0; i < $quantites.length; i++) {
                $quantites[i].innerText = $tabQuantiteBase[i] / $divise;
            }
        }
    }
    else{
        for (i = 0; i < $quantites.length; i++) {
            $quantites[i].innerText = "Nan";
        }
    }


});


$("#liste_course").click(function () {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = '_ingredient'; // 'the key/name of the attribute/field that is sent to the server
    input.value = '';
    $('div#test_div').each(function () {
        input.value = input.value + this.innerText;
        input.value = input.value + '\n';
    });
    var $form = $('#form_liste_courses');
    $form.append(input);
/*
var $titre = $("#titre").text();
var $myJson = JSON.stringify($titre);
var file = $('#username').val() + '.json';
localStorage.setItem(file, $myJson);
 */
});
