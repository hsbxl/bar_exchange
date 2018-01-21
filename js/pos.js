(function ($, window, Drupal) {

  'use strict';

  Drupal.behaviors.bar_exchange_pos = {
    attach: function () {

      $('#bar-exchange-pos table tr').click(function(c){
        var amount = +$(this).find('select').val() + 1;
        $(this).find('select').val(amount);
      });

    }
  };

})(jQuery, window, Drupal);