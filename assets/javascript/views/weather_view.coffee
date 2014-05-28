App.WeatherView = Ember.View.extend
  
  init: ->
    # Simply reload page to re-render graphs
    # instead of hacking into Ember from the outside in.
    window.onresize = (e) ->
      setTimeout ()->
        location.reload()
      , 500
    @_super()

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
    @rerender()
    @set('controller.loading', false)
  ).observes('controller.datasetOne')