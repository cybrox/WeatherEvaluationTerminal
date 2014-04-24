App.Router.map ()->
	@resource 'month', path: '/month/:month_id', ->
		@resource 'day', path: '/day/:day_id'