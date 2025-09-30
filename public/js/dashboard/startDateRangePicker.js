function startDateRangePicker(seletor, config) {
    const locale = {
        locale: {
            format: "DD/MM/YYYY", // Formato de data no Brasil
            separator: " - ",
            applyLabel: "Aplicar",
            cancelLabel: "Cancelar",
            fromLabel: "De",
            toLabel: "Até",
            customRangeLabel: "Personalizado",
            weekLabel: "S",
            daysOfWeek: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
            monthNames: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
            firstDay: 0, // Domingo como primeiro dia da semana
        },
    };
    const options = { ...locale, ...config };

    $(seletor).daterangepicker(options, function (start, end, label) {});
}