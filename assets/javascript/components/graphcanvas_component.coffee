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
    data = {labels: @generateLables(), datasets: @get('data')}
    data = @get('data') if @get('type') == 'PolarArea'
    chart = new Chart(context)[@get('type')](data, options)

  generateLables: ->
    lables = []
    if @get('range') == 'month'
      for m in [1..31]
        lables.push @stp(m)

    else if @get('range') == 'day'
      for h in [0...24]
        for m in [0...60] by 15
          lables.push @stp(h)+':'+@stp(m)

      # Remove some lables for space
      lables[(i-1)] = "" for i in [0..lables.length] by 2
        
    else if @get('range') == 'direction'
      lables = ['N', 'NO', 'O', 'SO', 'S', 'SW', 'W', 'NW']

    lables

  stp: (num) ->
    num = "0"+num if parseInt(num) < 10
    num