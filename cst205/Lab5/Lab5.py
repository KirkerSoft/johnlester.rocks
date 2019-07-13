# CST205 Lab 5 - Programmer: John Lester

def pyCopy(source, target, targetX, targetY):
  pic = makePicture(source)
  newPic = makePicture(target)
  for x in range(0, getWidth(pic)):
    if x + targetX < getWidth(newPic):
      for y in range(0, getHeight(pic)):
        if y + targetY < getHeight(newPic):
          pix = getPixel(pic, x, y)
          r = getRed(pix)
          g = getGreen(pix)
          b = getBlue(pix)
          if r != 0 and g != 215 and b != 0: # dont copy chroma key backgrounds
            newPix = getPixel(newPic, x + targetX, y + targetY)
            setRed(newPix, r)
            setGreen(newPix, g)
            setBlue(newPix, b)
  writePictureTo(newPic, target)
  return newPic
