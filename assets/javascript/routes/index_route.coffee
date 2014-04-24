App.IndexRoute = Ember.Route.extend
	beforeModel: ->
		@transitionTo 'month', @getCurrentMonth()

	getCurrentMonth: ->
		currentDate  = new Date()
		currentYear  = currentDate.getFullYear()
		currentMonth = currentDate.getMonth() + 1

		currentMonth = "0"+currentMonth if currentMonth < 10

		"#{currentYear}-#{currentMonth}"
