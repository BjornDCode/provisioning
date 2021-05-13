import React, { useState } from 'react'

import RadioListField from '@/Shared/Components/Fields/RadioListField'

const FieldHeader = ({ options, query, onChange }) => {
    const handleChange = event => {
        onChange(event.target.value)
    }

    return (
        <div className="mb-4">
            <input
                type="text"
                placeholder="Search..."
                className="block w-full px-8 py-3 rounded-md bg-gray-600 text-white border-0 focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-inset"
                value={query}
                onChange={handleChange}
            />
        </div>
    )
}

const SearchableRadioListField = ({ options = [], ...props }) => {
    const [query, setQuery] = useState('')

    const filteredOptions = options.filter(option =>
        option.label.toLowerCase().includes(query.toLowerCase())
    )

    return (
        <RadioListField
            Header={() => (
                <FieldHeader
                    query={query}
                    onChange={query => setQuery(query)}
                />
            )}
            options={filteredOptions}
            {...props}
        />
    )
}

export default SearchableRadioListField
