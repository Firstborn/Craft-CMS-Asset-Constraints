
(function($) {

   Craft.AssetConstraints = Garnish.Base.extend({
      $constraints: null,
      $constraintRow: null,
      rowId:0,

      init: function () {
         console.log('asset constraints');
         this.$constraints = $('#settings-assetConstraints');
         console.log(this.$constraints.length);
         this.$constraints.find('.btn.add').on('click', this.addConstraint.bind(this));
         this.$constraintRow = this.$constraints.find('tbody tr').first();

         console.log('before: ' + this.$constraintRow.html());

         //this.$constraintRow = $('table.assetConstraints')


      },

      addConstraint: function (event) {
         console.log('add constraints ' + this.rowId);
         this.rowId++;
         console.log('before: ' + this.$constraintRow.html());
         $tr = this.$constraintRow.clone();
         //console.log('before: ' + this.$constraintRow.html());
         //$tr.removeClass('target-row');
         console.log('after: ' + $tr.html());
         this.$constraints.append($tr);
         //new Craft.AssetConstraintRow($tr, this.rowId);
         return false;
      }


   });

   Craft.AssetConstraintRow = Garnish.Base.extend({

      $tr: null,

      init: function(element, rowId){

         /*
         this.$tr = element;

         this.addListener($tr.find('select[data-name="type"]'), 'change', 'setTargetOptions');
         this.addListener($tr.find('a.delete'), 'click', 'deleteTargetRow');

         this.$tr.find('.target-input').each(function(index,element)
         {
            var e = $(element);
            e.attr('name', 'targets[' + rowId + '][' + e.attr('data-name') + ']');

         });

         //if there is only one targeting option force it to be active
         var targetOptions = this.$tr.find('select[name*="type"] option');
         if (targetOptions.length == 1){
            this.$tr.find('select[name*="type"]').trigger('change');
         }*/


      },

      setTargetOptions: function(event){
         var value = $(event.target).val();
         this.$tr.find('.target-type').addClass('hidden');
         var select = this.$tr.find('.select[data-target="'+ value +'"]');
         select.removeClass('hidden');
      },

      deleteTargetRow: function(event){
         this.$tr.remove();
      }

   });

})(jQuery);
