/**
 * Save function for the custom Gutenberg block.
 *
 * @param {Object} props               Properties passed to the function.
 * @param {Object} props.attributes    Available block attributes.
 */
import { useBlockProps } from '@wordpress/block-editor';

export default function save({ attributes }) {
    const {
        paddings,
        margins,
        positions,
        listItems,
        iconSizes,
        itemsGap,
        divider,
        dividerColor,
        textColor,
        backgroundColor,
        backgroundColorFullWidth,
    } = attributes; // Destructure attributes

    return (
        <div
            {...useBlockProps.save()}
            style={{
                padding: paddings?.join(' ') || undefined,
                margin: margins?.join(' ') || undefined,
                position: positions?.join(' ') || undefined,
                backgroundColor: backgroundColorFullWidth ? backgroundColor : undefined,
                color: textColor,
            }}
        >
            {listItems?.map((item) => (
                <div key={item.id} className="list-item" style={{ gap: itemsGap }}>
                    {item.media && (
                        <div className="media">
                            <img src={item.media.url} alt={item.media.alt || ''} style={{ width: iconSizes }} />
                        </div>
                    )}
                    {item.text && (
                        <div className="text">
                            {item.isLink && item.url ? (
                                <a href={item.url} style={{ color: textColor }}>
                                    {item.text}
                                </a>
                            ) : (
                                item.text
                            )}
                        </div>
                    )}
                </div>
            ))}
            {divider && <hr style={{ borderColor: dividerColor }} />}
        </div>
    );
}