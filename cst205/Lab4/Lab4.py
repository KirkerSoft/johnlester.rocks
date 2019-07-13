# CST205 Lab 4 - Programmer: John Lester

filename = pickAFile()
pic = makePicture(filename)
show(pic)

def halfBetter(pic = pic):
  for x in range(getWidth(pic)/2, getWidth(pic)):
    for y in range(0, getHeight(pic)):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      intensity = (r * 0.299 + g * 0.587 + b * 0.114)
      setRed(pix, intensity)
      setGreen(pix, intensity)
      setBlue(pix, intensity)
  repaint(pic)
  return pic

def leftMirror(pic = pic):
  for x in range(0, getWidth(pic)/2):
    for y in range(0, getHeight(pic)):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(pic, getWidth(pic)-1-x, y)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  repaint(pic)
  return pic

def rightMirror(pic = pic):
  for x in range(getWidth(pic)/2, getWidth(pic)):
    for y in range(0, getHeight(pic)):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(pic, getWidth(pic)-1-x, y)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  repaint(pic)
  return pic

def topMirror(pic = pic):
  for x in range(0, getWidth(pic)):
    for y in range(0, getHeight(pic)/2):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(pic, x, getHeight(pic)-1-y)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  repaint(pic)
  return pic

def bottomMirror(pic = pic):
  for x in range(0, getWidth(pic)):
    for y in range(getHeight(pic)/2, getHeight(pic)):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(pic, x, getHeight(pic)-1-y)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  repaint(pic)
  return pic

def simpleCopy(pic = pic):
  newPic = makeEmptyPicture(getWidth(pic), getHeight(pic))
  for x in range(0, getWidth(pic)):
    for y in range(0, getHeight(pic)):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(newPic, x, y)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  show(newPic)
  return newPic

def rotatePic(pic = pic):
  newPic = makeEmptyPicture(getHeight(pic), getWidth(pic))
  for x in range(0, getWidth(pic)):
    for y in range(0, getHeight(pic)):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(newPic, y, x)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  repaint(newPic)
  return newPic

def reducePic(pic = pic):
  newPic = makeEmptyPicture(getHeight(pic)/2, getWidth(pic)/2)
  for x in range(0, getWidth(pic), 2):
    for y in range(0, getHeight(pic), 2):
      pix = getPixel(pic, x, y)
      r = getRed(pix)
      g = getGreen(pix)
      b = getBlue(pix)
      newPix = getPixel(newPic, x/2, y/2)
      setRed(newPix, r)
      setGreen(newPix, g)
      setBlue(newPix, b)
  repaint(newPic)
  return newPic
