# Usage: python seo/cutouts.py <out_dir> <shape> <src>...   shape: circle | arch
# Cuts the subject out of the theme's pink "circle" pictures and puts it on a flat shape in the site's palette.
import sys, os
import numpy as np
from scipy import ndimage
from PIL import Image, ImageDraw, ImageChops
from rembg import remove, new_session
out_dir, shape, srcs = sys.argv[1], sys.argv[2], sys.argv[3:]
BLUSH = (235, 217, 206)  # --lp-blush
session = new_session('isnet-general-use')
for src in srcs:
    im = Image.open(src).convert('RGBA')
    w, h = im.size
    cut = remove(im, session=session)
    # Never show more than the original showed: keep the original's own transparency.
    # Half-transparent leftovers of the old pink background are dropped: only confident pixels stay.
    sure = cut.getchannel('A').point(lambda v: 0 if v < 120 else min(255, (v - 120) * 255 // 90))
    alpha = ImageChops.multiply(sure, im.getchannel('A'))
    # Patches of the old pink backdrop the model kept (between gloves and hair, under an arm): pale pink
    # areas that touch what was already removed go too. Gloves are white and skin is darker, so they stay.
    rgb = np.asarray(im.convert('RGB')).astype(int)
    r, g, b = rgb[..., 0], rgb[..., 1], rgb[..., 2]
    kept = np.asarray(alpha) > 0
    pink = kept & (r > 228) & (g > 195) & (b > 185) & (r - b > 20) & (r - b < 70) & (r - g > 9)
    labels, n = ndimage.label(pink)
    near_removed = ndimage.binary_dilation(~kept & (np.asarray(im.getchannel('A')) > 0), iterations=4)
    drop = np.zeros_like(kept)
    for i in range(1, n + 1):
        patch = labels == i
        if patch.sum() > 150 and (patch & near_removed).any():
            drop |= patch
    drop = ndimage.binary_dilation(drop, iterations=2)
    alpha = Image.fromarray(np.where(drop, 0, np.asarray(alpha)).astype('uint8'))
    subject = im.copy(); subject.putalpha(alpha)
    S = 4
    mask = Image.new('L', (w * S, h * S), 0)
    d = ImageDraw.Draw(mask)
    if shape == 'circle':
        m = int(0.035 * w * S)
        d.ellipse((m, m, w * S - m, h * S - m), fill=255)
    else:
        d.pieslice((0, 0, w * S, 2 * h * S), 180, 360, fill=255)
    mask = mask.resize((w, h), Image.LANCZOS)
    base = Image.new('RGBA', (w, h), BLUSH + (0,))
    base.putalpha(mask)
    out = Image.alpha_composite(base, subject)
    # The shape clips the subject too, as the original circle did.
    out.putalpha(ImageChops.multiply(out.getchannel('A'), mask))
    name = os.path.splitext(os.path.basename(src))[0] + '.png'
    out.save(os.path.join(out_dir, name), optimize=True)
    print(name, out.size)
