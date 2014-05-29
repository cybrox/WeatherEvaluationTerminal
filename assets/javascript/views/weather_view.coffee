App.WeatherView = Ember.View.extend

  lineOptions: {
    datasetFill: false
    scaleLineWidth: 2
    pointDotRadius: 1
    pointDotStrokeWidth: 2
    animationSteps: 20
  }

  areaOptions: {
    animationSteps: 20
  }

  updateGraphs: (->
    @set('controller.loading', false)
  ).observes('controller.datasetOne')