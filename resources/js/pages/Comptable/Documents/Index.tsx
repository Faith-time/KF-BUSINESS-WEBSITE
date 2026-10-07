import type { ReactNode } from 'react';
import { Head } from '@inertiajs/react';
import ComptableLayout from '@/layouts/comptable-layout';
import { AccountantDocuments } from '@/screens/accountant';

export default function Page() {
  return (
    <>
      <Head title="Documents" />
      <AccountantDocuments />
    </>
  );
}

Page.layout = (page: ReactNode) => <ComptableLayout>{page}</ComptableLayout>;
