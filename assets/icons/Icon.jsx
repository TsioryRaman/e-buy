import React from "react";
/**
 * Icône basé sur la sprite SVG
 * @param {{name: string}} props
 */
export function Icon ({ name, size = 24, className }) {
    const href = `/assets/icons/sprite.svg#${name}`
    return (
      <svg className={className} width={size} height={size}>
        <use xlinkHref={href} />
      </svg>
    )
  }