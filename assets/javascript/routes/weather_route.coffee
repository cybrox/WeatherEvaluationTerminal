App.WeatherRoute = Ember.Route.extend
  model: (params) ->
    @set 'year',  params.year
    @set 'month', params.month

  setupController: (controller, model) ->
    @_super(controller, model)
    @controllerFor('weather').setProperties('year': @get('year'), 'month': @get('month'))