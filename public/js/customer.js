Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-vue',

  data: {
    items: [],
    pagination: {
        total: 0, 
        per_page: 2,
        from: 1, 
        to: 0,
        current_page: 1
      },
    offset: 4,
    formErrors:{},
    formErrorsUpdate:{},
    newItem : {'name':'','company_name':'','vendor_id':''},
    fillItem : {'name':'','company_name':'','id':'','vendor_id':''}
  },

  computed: {
        isActived: function () {
            return this.pagination.current_page;
        },
        pagesNumber: function () {
            if (!this.pagination.to) {
                return [];
            }
            var from = this.pagination.current_page - this.offset;
            if (from < 1) {
                from = 1;
            }
            var to = from + (this.offset * 2);
            if (to >= this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            var pagesArray = [];
            while (from <= to) {
                pagesArray.push(from);
                from++;
            }
            return pagesArray;
        }
    },

  ready : function(){
  		this.getVueItems(this.pagination.current_page);
  },

  methods : {

        getVueItems: function(page){
          this.$http.get('/customer?page='+page).then((response) => {
            this.$set('items', response.data.data);
            this.$set('pagination', response.data.pagination);
          });
        },

    createCustomer: function(){
		  var input = this.newItem;
      this.$http.post('/customer',input).then((response) => {
		  this.changePage(this.pagination.current_page);
			this.newItem = {'name':'','company_name':'','vendor_id':''};
			$("#create-item").modal('hide');
			toastr.success('Customer Created Successfully.', 'Success Alert', {timeOut: 5000});
		  }, (response) => {
			this.formErrors = response.data;
	    });
	},

      deleteCustomer: function(item){
        this.$http.delete('/customer/'+item.id).then((response) => {
            this.changePage(this.pagination.current_page);
            toastr.success('Customer Deleted Successfully.', 'Success Alert', {timeOut: 5000});
        });
      },

      editCustomer: function(item){
          this.fillItem.name = item.name;
          this.fillItem.id = item.id;
          this.fillItem.company_name = item.company_name;
          this.fillItem.vendor_id = item.vendor_id;
          $("#edit-item").modal('show');
      },

      updateItem: function(id){
        var input = this.fillItem;
        this.$http.put('/customer/'+id,input).then((response) => {
            this.changePage(this.pagination.current_page);
            this.fillItem = {'name':'','company_name':'','vendor_id':'','id':''};
            $("#edit-item").modal('hide');
            toastr.success('Customer Updated Successfully.', 'Success Alert', {timeOut: 5000});
          }, (response) => {
              this.formErrorsUpdate = response.data;
          });
      },

      changePage: function (page) {
          this.pagination.current_page = page;
          this.getVueItems(page);
      }

  }

});