import ptBR from "../../node_modules/flowbite-datepicker/js/i18n/locales/pt-BR.js";

window.addEventListener("load", () => {
    setTimeout(() => {
        let locales = {
            ptBR: ptBR["pt-BR"],
        };

        let flowbitePickers = Object.values(FlowbiteInstances.getInstances("Datepicker")).map((instance) => {
            return instance.getDatepickerInstance();
        });

        for (const flowbitePicker of flowbitePickers) {
            for (const picker of flowbitePicker.datepickers || [flowbitePicker]) {
                Object.assign(picker.constructor.locales, locales);
                picker.setOptions({
                    language: "ptBR",
                    format: "dd/mm/yyyy",
                });
            }
        }
    }, 100);
});
