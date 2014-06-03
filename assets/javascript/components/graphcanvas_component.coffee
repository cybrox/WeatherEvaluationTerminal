App.GraphCanvasComponent = Ember.Component.extend
  tagName: 'canvas'

  didInsertElement: ->
    canvas = @get('element')
    jqcanv = $(canvas)
    context = canvas.getContext('2d')
    canvas.width = jqcanv.parent().width()
    canvas.height = 200#jqcanv.parent().height()

    options = {}
    options = @get('options') if @get('options') != undefined
    @setProperties({'canvas': canvas, 'options': options})
    @onUpdateElement()

  graphData: (->
    data = {labels: @generateLables(), datasets: @get('data')}
    data = @get('data') if @get('type') == 'PolarArea'
    data
  ).property('data')

  onUpdateElement: (->
    context = @get('canvas').getContext('2d')
    @set('chart', new Chart(context)[@get('type')](@get('graphData'), @get('options')))
  ).observes('data')

  generateLables: ->
    lables = []
    if @get('range') == 'month'
      for m in [1..31]
        lables.push @stp(m)

    else if @get('range') == 'day'
      for h in [0..24]
          lables.push @stp(h)+':00'
          lables.push ''
          lables.push ''
          lables.push ''
        
    else if @get('range') == 'direction'
      lables = ['N', 'NO', 'O', 'SO', 'S', 'SW', 'W', 'NW']

    lables

  stp: (num) ->
    num = "0"+num if parseInt(num) < 10
    num