# CST205 Bonus - Programmer: John Lester

pic = makePicture(pickAFile())
show(pic)
pic = lineDrawing(pic)

def betterBnW(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    g = getGreen(pix)
    b = getBlue(pix)
    intensity = (r * 0.299 + g * 0.587 + b * 0.114)
    setRed(pix, intensity)
    setGreen(pix, intensity)
    setBlue(pix, intensity)
  repaint(pic)
  return pic

def lineDrawing(pic = pic):
  pic = betterBnW(pic)
  threshold = 32
  for x in range(0, getWidth(pic)):
    for y in range(0, getHeight(pic)):
      pix = getPixel(pic, x, y)
      lum = getColor(pix)
      if y < getHeight(pic)-1:
        bpix = getPixel(pic, x, y+1)
      blum = getColor(bpix)
      if x < getWidth(pic)-1:
        rpix = getPixel(pic, x+1, y)
      rlum = getColor(rpix)
      if distance(lum, blum) > threshold and distance(lum, rlum) > threshold:
        setColor(pix, black)
      else:
        setColor(pix, white)
  repaint(pic)
  return pic
