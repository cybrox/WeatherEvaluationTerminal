App.WeatherController = Ember.Controller.extend
  
  year: 0

  blip: (->
    console.log @get 'year'
  ).property('year')