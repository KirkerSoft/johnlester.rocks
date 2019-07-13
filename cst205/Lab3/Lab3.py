# CST205 Lab 3 - Programmer: John Lester

filename = pickAFile()
pic = makePicture(filename)
show(pic)

def halfRed(pic = pic):
  pic = lessRed(50)
  return pic

def noBlue(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    setBlue(pix, 0)
  repaint(pic)
  return pic

def lessRed(amount, pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    setRed(pix, r - r * amount * 0.01)
  repaint(pic)
  return pic

def moreRed(amount, pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    mr = r + r * amount * 0.01
    if mr >= 255:
      setRed(pix, 255)
    else:
      setRed(pix, mr)
  repaint(pic)
  return pic

def roseColoredGlasses(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    g = getGreen(pix)
    b = getBlue(pix)
    if r <= 212:
      setRed(pix, r * 1.2)
    else:
      setRed(pix, 255)
    setGreen(pix, g * 0.4)
    setBlue(pix, b * 0.8)
  repaint(pic)
  return pic

def lightenUp(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    c = getColor(pix)
    nc = makeLighter(c)
    setColor(pix, nc)
  repaint(pic)
  return pic

def makeNegative(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    g = getGreen(pix)
    b = getBlue(pix)
    setRed(pix, 255 - r)
    setGreen(pix, 255 - g)
    setBlue(pix, 255 - b)
  repaint(pic)
  return pic

def BnW(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    g = getGreen(pix)
    b = getBlue(pix)
    intensity = int((r + g + b) / 3)
    setRed(pix, intensity)
    setGreen(pix, intensity)
    setBlue(pix, intensity)
  repaint(pic)
  return pic

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
