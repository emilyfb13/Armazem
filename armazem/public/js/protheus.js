$(function () {

 $(document).ready(function() {
      $('.select2-single').select2({
        theme: 'bootstrap4'
      });
    });


  $('.dates').daterangepicker({ 
      //"singleDatePicker": true, 
      //"showDropdowns" : true,
      "locale": {
        "format": "DD/MM/YYYY",
        "separator": " - ",
        "applyLabel": "Aplicar",
        "cancelLabel": "Cancelar",
        "fromLabel": "De",
        "toLabel": "Até",
        "customRangeLabel": "Custom",
        "daysOfWeek": [
            "Dom",
            "Seg",
            "Ter",
            "Qua",
            "Qui",
            "Sex",
            "Sáb"
        ],
        "monthNames": [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro"
        ],
        "firstDay": 0
      }
    });


   
    





 
 


 


    





  $("#btnProdutos").on('click', function () {
      $("#msgload").css
      ({
          display: "block",
          visibility: "visible"
      });

      $("#btnload").css
      ({
          display: "inline",
          visibility: "visible"
      });

  });
});

