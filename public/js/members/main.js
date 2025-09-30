$(document).ready(function () {
    function toggleContent($component) {
        var $checkbox = $component.find('input[type="checkbox"]');
        var $toggleContent = $component.find(".toggleContent");

        if ($checkbox.is(":checked")) {
            $toggleContent.show();
        } else {
            $toggleContent.hide();
        }
    }

    $('[id^="toggle-component-"]').each(function () {
        var $component = $(this);
        
        toggleContent($component);

        $component.find('input[type="checkbox"]').change(function () {
            toggleContent($component);
        });
    });
});

$(document).ready(function () {
    function toggleContent($component) {
        var $checkbox = $component.find('input[type="radio"]');
        var $toggleContent = $component.find(".toggleContent");

        if ($checkbox.is(":checked")) {
            $toggleContent.show();
        } else {
            $toggleContent.hide();
        }
    }

    $('[id^="toggle-component-"]').each(function () {
        var $component = $(this);
        toggleContent($component);
        $component.find('input[type="radio"]').change(function () {
            $(".toggleContent").hide();
            toggleContent($component);
        });
    });
});
