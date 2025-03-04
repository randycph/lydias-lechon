$(document).ready(function() {
    initialize_owl($('#owl1'));

        var count = parseFloat($('input[name="catcount"]').val());
        let tabs = [];
        for(var i=1; i<=count; i++) {
            tabs.push({target: "#menucat"+i, owl: "#owl"+i});
        }

    // let tabs = [{
    //     target: '#menucat1',
    //     owl: '#owl1'
    // },];

    // Setup 'bs.tab' event listeners for each tab
    tabs.forEach((tab) => {
        $(`a[href="${ tab.target }"]`)
            .on('shown.bs.tab', () => initialize_owl($(tab.owl)))
            .on('hide.bs.tab', () => initialize_owl($(tab.owl)))
            .on('shown.bs.tab', () => initialize_owl($(tab.owl)))
            .on('hide.bs.tab', () => initialize_owl($(tab.owl)));
    });
});

function initialize_owl(el) {
    el.owlCarousel({
        loop: true,
        margin: 10,
        dots: false,
        nav: true,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });
}

function destroy_owl(el) {
    el.data('owlCarousel').destroy();
}
