import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router } from '@inertiajs/react';
import { Ziggy } from '@/ziggy';
import Pagination from '@/Components/pagination';
import TextInput from '@/Components/TextInput';
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/react/16/solid'

export default function Index({ auth, users, queryParams = null, success }) {

    queryParams = queryParams || {}
    const searchFieldChanged = (nom, value) => {
        if(value){
            queryParams[nom] = value
        } else {
            delete queryParams[nom]
        }

        router.get(route("user.index", queryParams))
    }

    const deleteUser = (user) => {
        if(!window.confirm('Etes-vous sûr de vouloir supprimer cet utilisateur ?')) {
            return;
        }
        router.delete(route("user.destroy", user.id))
    }

    const onKeyPress = (nom, e) => {
        if(e.key !== 'Enter') return;

        searchFieldChanged(nom, e.target.value);
    }
    const sortChanged = (nom) => {
        if(nom === queryParams.sort_field) {
            if(queryParams.sort_direction === 'asc') {
                queryParams.sort_direction = 'desc';
            } else {
                queryParams.sort_direction = 'asc';
            }
        } else {
            queryParams.sort_field = nom;
            queryParams.sort_direction = 'asc';
        }

        router.get(route("user.index", queryParams))
    }

    return(
        <AuthenticatedLayout
            user={auth.user}
            header={
                <div className="flex justify-between items-center">
                    <h2 className="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        Liste des Utilisateurs
                    </h2>
                    <Link className="bg-emerald-500 text-white font-bold py-1 px-3 rounded shadow transition-all hover:bg-emerald-400" href={route("user.create")}>
                        Ajouter un Utilisateur
                    </Link>
                </div>

            }
        >

            <Head title="Users" />
            {success &&
            (<div className="bg-emerald-500 py-2 px-4 text-white rounded">
                {success}
            </div>)
            }

            <div className="py-12">
                <div className="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div className="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                        <div className="p-6 text-gray-900 dark:text-gray-100">
                            <table className="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2 border-gray-500">
                                    <tr className="text-nowrap">
                                    <th onClick={(e) => sortChanged("id")} className="px-3 py-2"><div className=" flex items-center justify-between gap-1 cursor-pointer">ID <div><ChevronUpIcon className="w-4" /><ChevronDownIcon className="w-4 -mt-2" /></div></div></th>
                                    <th onClick={(e) =>sortChanged("nom")} className="px-3 py-2"><div className="flex items-center justify-between gap-1 cursor-pointer">Nom <div><ChevronUpIcon className="w-4" /><ChevronDownIcon className="w-4 -mt-2" /></div></div></th>
                                    <th onClick={(e) =>sortChanged("email")} className="px-3 py-2"><div className="flex items-center justify-between gap-1 cursor-pointer">Email <div><ChevronUpIcon className="w-4" /><ChevronDownIcon className="w-4 -mt-2" /></div></div></th>
                                    <th onClick={(e) => sortChanged("role")} className="px-3 py-2"><div className="flex items-center justify-between gap-1 cursor-pointer">Role <div><ChevronUpIcon className="w-4" /><ChevronDownIcon className="w-4 -mt-2" /></div></div></th>
                                    <th className="px-3 py-2">Actions</th>
                                    </tr>
                                </thead>
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b-2 border-gray-500">
                                    <tr className="text-nowrap">
                                        <th className="px-3 py-2"></th>
                                        <th className="px-3 py-2">
                                            <TextInput className="w-full"
                                                defaultValue={queryParams.nom}
                                                placeholder="chercher avec le nom de l'utilisateur"
                                                onBlur={e =>{ searchFieldChanged('nom', e.target.value)}}
                                                onKeyPress={e => onKeyPress('nom', e)}
                                            />
                                        </th>
                                        <th className="px-3 py-2">
                                            <TextInput className="w-full"
                                                defaultValue={queryParams.email}
                                                placeholder="chercher avec l'email de l'utilisateur"
                                                onBlur={e =>{ searchFieldChanged('email', e.target.value)}}
                                                onKeyPress={e => onKeyPress('email', e)}
                                            />
                                        </th>
                                        <th className="px-3 py-2"></th>
                                        <th className="px-3 py-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                {users.data && users.data.map((user) => (
                                    <tr key={user.id} className="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td className="px-3 py-2">{user.id}</td>
                                        <td className="px-3 py-2">{user.nom}</td>
                                        <td className="px-3 py-2">{user.email}</td>
                                        <td className="px-3 py-2">{user.role.nom}</td>
                                        <td className="px-3 py-2 text-nowrap">
                                            <Link href={route('user.edit', user.id)} className="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-4">Modifier</Link>
                                            <button onClick={(e) => deleteUser(user)} className="font-medium text-red-600 dark:text-red-500 hover:underline mr-4">Supprimer</button>
                                        </td>
                                    </tr>
                                ))}
                                </tbody>

                            </table>
                            <Pagination links={users.meta.links} />
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )

}
