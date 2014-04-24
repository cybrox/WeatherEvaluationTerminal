App.MonthRoute = Ember.Route.extend
	model: (params) ->
		date = params.month_id.split '-'

		@set 'year',  date[0]
		@set 'month', date[1]

		Ember.$.getJSON "api/get.php?f="+params.month_id