App.WeatherRoute = Ember.Route.extend
  model: (params) ->
    @set 'year',  params.year
    @set 'month', params.month

    $.getJSON "api/get/weatherdata/"+params.year+"-"+params.month, (payload) =>
      if(payload.success) then payload else @transitionToRoute('notfound')

  setupController: (controller, model) ->
    @_super(controller, model)
    @controllerFor('weather').setProperties('year': @get('year'), 'month': @get('month'))