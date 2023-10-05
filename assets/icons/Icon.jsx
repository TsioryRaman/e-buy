/**
 * Icône basé sur la sprite SVG
 * @param {{name: string}} props
 */
export function Icon ({ name, size = 24 }) {
    const className = `icon icon-${name}`
    const href = `./feather/${name}.svg`
    return (
      <svg className={className} width={size} height={size}>
        <use xlinkHref={href} />
      </svg>
    )
  }