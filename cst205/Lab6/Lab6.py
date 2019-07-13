# CST205 Lab 6 - Programmer: John Lester

filename = pickAFile()
pic = makePicture(filename)
show(pic)

def redEyeRabbit(newColor, pic = pic):
  for x in range(780, 1000): # specific to image
    for y in range(250, 470): # specific to image
      px = getPixel(pic, x, y)
      color = getColor(px) # even color selection is specific to this image
      if (distance(color, Color(211, 87, 106)) < 50) or (distance(color, Color(238, 153, 129)) < 50):
        setColor(px, newColor)
      if (distance(color, Color(255, 249, 160)) < 50) or (distance(color, Color(248, 214, 95)) < 50):
        setColor(px, newColor)
  show(pic)
  return pic

def sepia(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    g = getGreen(pix)
    b = getBlue(pix)
    intensity = (r * 0.299 + g * 0.587 + b * 0.114)
    r = intensity
    g = intensity
    b = intensity
    if r < 63:
      r = r * 1.1
      b = b * 0.9
    elif r < 192:
      r = r * 1.15
      b = b * 0.85
    else:
      if r * 1.08 > 255:
        r = 255
      else:
        r = r * 1.08
      b = b * 0.93
    setRed(pix, r)
    setGreen(pix, g)
  show(pic)
  return pic

def Artify(pic = pic):
  pixels = getPixels(pic)
  for pix in pixels:
    r = getRed(pix)
    g = getGreen(pix)
    b = getBlue(pix)
    if r < 64:
      setRed(pix, 31)
    elif r < 128:
      setRed(pix, 95)
    elif r < 192:
      setRed(pix, 159)
    else:
      setRed(pix, 223)
    if g < 64:
      setGreen(pix, 31)
    elif g < 128:
      setGreen(pix, 95)
    elif g < 192:
      setGreen(pix, 159)
    else:
      setGreen(pix, 223)
    if b < 64:
      setBlue(pix, 31)
    elif b < 128:
      setBlue(pix, 95)
    elif b < 192:
      setBlue(pix, 159)
    else:
      setBlue(pix, 223)
  show(pic)
  return pic

def chromakey(addition, targetFile, targetX, targetY, overRide = False):
  newPic = makePicture(addition)
  bckPic = makePicture(targetFile)
  for x in range(0, getWidth(newPic)):
    if x + targetX < getWidth(bckPic):
      for y in range(0, getHeight(newPic)):
        if y + targetY < getHeight(bckPic):
          pix = getPixel(newPic, x, y)
          bPix = getPixel(bckPic, x + targetX, y + targetY)
          color = getColor(pix)
          bColor = getColor(bPix)
          if (distance(color, Color(0, 215, 0)) > 50) and ((distance(bColor, Color(0, 215, 0)) < 60) or (distance(bColor, Color(0, 131, 0)) < 60) or (distance(bColor, Color(45, 221, 45)) < 60) or overRide):
            newPix = getPixel(bckPic, x + targetX, y + targetY)
            setColor(bPix, color)
  writePictureTo(bckPic, targetFile)
  show(bckPic)
  return bckPic
