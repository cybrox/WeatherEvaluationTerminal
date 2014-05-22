App.GraphCanvasComponent = Ember.Component.extend
  tagName: 'canvas'

  canvas: null
  context: null

  didInsertElement: ->
    canvas = @get('element')
    jqcanv = $(canvas)
    context = canvas.getContext('2d')

    @setProperties('canvas': canvas, 'context': context);

    canvas.width = jqcanv.parent().width()
    canvas.height = jqcanv.parent().height()

    @initializeGraph()

  initializeGraph: ->
    if @get('gtype') != undefined
      if @get('gtype') == 'line' then  @set('chart', new Chart(@get('context')).Line(@get('gdata'), @get('goptions')))
      if @get('gtype') == 'radar' then @set('chart', new Chart(@get('context')).Radar(@get('gdata'), @get('goptions')))
    else
      console.log('invalid graph type specified.')