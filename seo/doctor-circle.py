# Recreates the old doctor portrait style from a new photo: subject cut out over a pale blush disk with a thin
# rose ring, head breaking out above the circle. Geometry and colors measured from the previous dr-bruno-ocampo.webp.
# Usage: python seo/doctor-circle.py <src> <out.webp> [preview.png]   (needs rembg, Pillow: pip install "rembg[cpu]" pillow)
import sys
from PIL import Image, ImageDraw, ImageChops
from rembg import remove, new_session

src, out_path = sys.argv[1], sys.argv[2]
W, H = 900, 1166                      # same 0.772 ratio as the old 768x995 file; template uses 900x1166
S = 4                                 # supersampling for smooth edges
R = W / 2                             # ring radius: the circle spans the full width and sits on the bottom
CY = H - R
GAP = 0.036 * W                       # transparent gap between the ring and the disk, as in the old file
RING = (191, 145, 122, 110)
DISK = (255, 245, 239, 255)

cut = remove(Image.open(src).convert('RGBA'), session=new_session('isnet-general-use'))
cut = cut.crop(cut.getchannel('A').point(lambda v: 255 if v > 40 else 0).getbbox())

# Shoulders fill the disk; the head top sits a little below the canvas top.
scale = (2 * (R - GAP)) * 1.02 / cut.width
subj = cut.resize((round(cut.width * scale), round(cut.height * scale)), Image.LANCZOS)
top = round(0.012 * H)
if top + subj.height < H:
    sys.exit(f'subject too short: bottom at {top + subj.height} < {H}; the photo edge would show inside the disk')
layer = Image.new('RGBA', (W, H), (0, 0, 0, 0))
layer.alpha_composite(subj, ((W - subj.width) // 2, top) if subj.width <= W else (0, top),
                      (0, 0) if subj.width <= W else ((subj.width - W) // 2, 0))

def disk_mask(radius, width=0):
    m = Image.new('L', (W * S, H * S), 0)
    box = ((W / 2 - radius) * S, (CY - radius) * S, (W / 2 + radius) * S, (CY + radius) * S)
    ImageDraw.Draw(m).ellipse(box, fill=None if width else 255, outline=255 if width else None, width=width * S)
    return m.resize((W, H), Image.LANCZOS)

inner = disk_mask(R - GAP)
ring = disk_mask(R - 1.5, width=2)

base = Image.new('RGBA', (W, H), (0, 0, 0, 0))
ring_layer = Image.new('RGBA', (W, H), RING); ring_layer.putalpha(ImageChops.multiply(ring, Image.new('L', (W, H), RING[3])))
disk_layer = Image.new('RGBA', (W, H), DISK); disk_layer.putalpha(inner)
base = Image.alpha_composite(Image.alpha_composite(base, ring_layer), disk_layer)

# The disk clips the body and the photo-cut shoulders; only the head and hair, high above center, break out.
clip = inner.copy()
ImageDraw.Draw(clip).rectangle((0, 0, W, int(CY - 0.4 * R)), fill=255)
layer.putalpha(ImageChops.multiply(layer.getchannel('A'), clip))
out = Image.alpha_composite(base, layer)
out.save(out_path, 'WEBP', quality=86, method=6)
if len(sys.argv) > 3:
    bg = Image.new('RGBA', (W, H), (250, 246, 242, 255)); bg.alpha_composite(out); bg.convert('RGB').resize((W // 3, H // 3)).save(sys.argv[3])
print(out_path, out.size)
