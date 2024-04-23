        var $ = jQuery;
        // Empty DOM element.
        $('#edit-start-date').change(function() {
	      var start_date = $('#edit-start-date').val();

          var sdate = new Date(start_date);
          if(start_date != NaN) {
            sdate.setDate(sdate.getDate() - 14);
            var get_started_date = new Date(sdate);
            if(get_started_date.getMonth() + 1 <= 9 && get_started_date.getDate() <= 9){
              var dateMDY = `${get_started_date.getFullYear()}-`+0+`${get_started_date.getMonth() + 1}-`+0+`${get_started_date.getDate()}`;
            }else if(get_started_date.getMonth() + 1 <= 9 && get_started_date.getDate() > 9){
              var dateMDY = `${get_started_date.getFullYear()}-`+0+`${get_started_date.getMonth() + 1}-${get_started_date.getDate()}`;
            }else if(get_started_date.getMonth() + 1 > 9 && get_started_date.getDate() <= 9){
              var dateMDY = `${get_started_date.getFullYear()}-${get_started_date.getMonth() + 1}-`+0+`${get_started_date.getDate()}`;
            }else{
              var dateMDY = `${get_started_date.getFullYear()}-${get_started_date.getMonth() + 1}-${get_started_date.getDate()}`;
            }
            
              $('#edit-get-start-date').val(dateMDY);
          }
        });
      